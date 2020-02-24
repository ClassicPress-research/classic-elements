#!/usr/bin/env bash

set -e

cd "$(dirname "$0")"
cd ..

set -x

export PLUGIN_VERSION=$(grep 'ELEMENTOR_VERSION' elementor.php | cut -d\' -f4)
export PLUGIN_ZIP_FILENAME="classic-elementor-${PLUGIN_VERSION}.zip"
rm -rf $PLUGIN_ZIP_FILENAME elementor
grunt build
mv build elementor
zip -r $PLUGIN_ZIP_FILENAME elementor
