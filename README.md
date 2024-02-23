# TypeScript Workshop

Welcome to the PEP TypeScript Workshop. This workshop is designed to familiarize engineering teams with how to read and write TypeScript. Working in TypeScript makes the JavaScript that runs on our brands more readable and less prone to bugs. This repository contains the lab portion of the workshop.

## Completing the Lab Work

The following is the pmc-sponsored-posts plugin before it was converted to TypeScript [in this pull request](https://github.com/penske-media-corp/pmc-plugins/pull/636).

Within the [`src/js`](src/js) directores are a set of JavaScript files, each with a varying degree of difficulty. During the lab portion of the workshop, you will work to convert the JavaScript files to TypeScript equivalents, ensuring that the newly-created TypeScript files contain the proper typing so that the TypeScript compiler can ensure that your code is free from bugs that can stem from type mismatches.

## Building and Testing

This repository uses [`@wordpress/scripts`](https://www.npmjs.com/package/@wordpress/scripts) for dependencies, building, and testing. To set up the dependencies, first ensure that you [have Node Version Manager installed](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating), then `cd` into the folder containing this repository and run the following commands:
```
nvm install
nvm use
npm ci
```
Once you've updated updated a file to TypeScript, you can run you tests (in the same terminal you ran the commands in from above) by running:
```
npm run test:unit
```
You can also transpile your TypeScript files to JavaScript using:
```
npm run build
```
Once you have the files transpiled, you can see how the TypeScript compiler converts them to JavaScript by inspecting the JavaScript files in your `build/index.js` file (though it _is_ minified).
