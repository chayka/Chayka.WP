#!/usr/bin/env bash
phpdoc -d ./src/ -t ./docs/ --cache-folder="./docs/.cache" --template="xml"
phpdocmd ./docs/structure.xml ./docs/
rm -R ./docs/.cache ./docs/structure.xml
mv ./docs/ApiIndex.md ./docs/readme.md
