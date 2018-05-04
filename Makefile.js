"use strict";

const del = require("del");
const path = require("path");
const fs = require("fs");
const execa = require("execa");
const through = require('through');

const publicPath = path.join(__dirname, "public");
const nodeModulesPath = path.join(__dirname, "node_modules");
const ravenJSPathDist = path.join(nodeModulesPath, "raven-js/dist");

// Update package json version field also

Promise.resolve()
  .then(() => del([`${publicPath}/**/*`]))
  .then(() => execa("npm", ["outdated", "raven-js"])
    .catch((error) => {
      console.log(error.stdout);
      console.log("[npm] Update `raven-js` package.");

      if (error.code === 1) {
        return execa("npm", ["install", "raven-js@latest", "--save-dev", "--save-exact"])
      }
    })
  )
  .then(() => execa("composer", ["outdated", "sentry/sentry", "--strict"])
    .catch((error) => {
      console.log(error.stdout);
      console.log("[composer] Update `sentry/sentry` package.");

      if (error.code === 1) {
        return execa(
          "composer",
          [
            "require",
            "sentry/sentry",
            "--prefer-stable",
            "--prefer-dist",
            "--no-suggest"
          ]
        )
      }
    })
  )
  .then(() =>
    fs.createReadStream(path.join(ravenJSPathDist, "raven.min.js"))
      .pipe(fs.createWriteStream(path.join(publicPath, "raven.min.js")))
  )
  .then(() =>
    fs.createReadStream(path.join(ravenJSPathDist, "raven.min.js"))
      .pipe(through(
        function write(buf) {
          this.emit(
            'data',
            buf
              .toString()
              .replace("//# sourceMappingURL=raven.min.js.map", "")
              .trim()
          );
        },
        function end() {
          this.emit('end');
        })
      )
      .pipe(fs.createWriteStream(path.join(publicPath, "raven-hidden-source-map.min.js")))
  )
  .then(() => {
    fs.createReadStream(path.join(ravenJSPathDist, "raven.min.js.map"))
      .pipe(fs.createWriteStream(path.join(publicPath, "raven.min.js.map")))
  })
  .then(() => {
    execa(
      "composer",
      [
        "install",
        "--no-dev",
        "--optimize-autoloader",
        "--classmap-authoritative"
      ],
      {
        stdio: "inherit"
      }
    )
  })
  .catch(error => {
    console.log(error.stack); // eslint-disable-line no-console
    const exitCode = typeof err.code === "number" ? error.code : 1;
    process.exit(exitCode); // eslint-disable-line no-process-exit
  });
