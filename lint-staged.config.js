"use strict";

module.exports = {
  "*.php": ["prettier --list-different", "git add"],
  "*.{md,markdown,mdown,mkdn,mkd,mdwn,mkdown,ron}": [
    "prettier --list-different",
    "remark -f -q",
    "git add"
  ],
  "*.{yml,yaml}": ["prettier --list-different", "git add"]
};
