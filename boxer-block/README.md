# Boxer

Boxer puts your WordPress posts into boxes.

## Introduction

This is an experiment to build a JavaScript-based, single block plugin to be fit for [The Block Directory](https://make.wordpress.org/meta/2019/03/08/the-block-directory-and-a-new-type-of-plugin/).

## Considerations:
* Create a JavaScript-based [dynamic block](https://developer.wordpress.org/block-editor/tutorials/block-tutorial/creating-dynamic-blocks/) without server side rendering.
* Experiment with rendering React component in `view` mode, as well as `edit` mode. 
* Take advantage of [wp-scripts](https://developer.wordpress.org/block-editor/packages/packages-scripts/) for Gutenberg block dev tooling.
* Take advantage of [wp-data](https://developer.wordpress.org/block-editor/packages/packages-data/) to access server-side core WordPress entities.
* Explore `InspectorControls` and `BlockControls` to configure a block.
* Boxer is inspired by :
  * https://github.com/WordPress/gutenberg-examples
  * https://github.com/JimSchofield/guty-blocks/blob/master/src/react-view/react-view.view.js
