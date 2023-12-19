# TypeScript Workshop
Welcome to the PEP TypeScript Workshop! This workshop is designed to familiarize Engineering teams with how to read and write TypeScript. Working in TypeScript makes the JavaScript that runs on our brands more readable and less prone to bugs. This repository contains the lab portion of the workshop.

## Completing the Lab Work
Within the [`src/js`](src/js) directores are a set of JavaScript files, each with a varying degree of difficulty. During the lab portion of the workshop, you will work to convert the JavaScript files to TypeScript equivalents, ensuring that the newly-created TypeScript files contain the proper typing so that the TypeScript compiler can ensure that your code is free from bugs that can stem from type mismatches.

For each file that you convert, start by renaming the file and its corresponding test in the `__tests__` folder from having a `.js` extension to having a `.ts` extension. Once that's done, you can begin converting the file to TypeScript. Successful completion of each conversion consists of converted files and tests with proper typing such that the TypeScript compiler does not complain and the tests pass.

If you are new to TypeScript, we suggest that you start by converting the [`src/js/number.js`](src/js/number.js) file to TypeScript. The other files within the [`src/js`](src/js) directory that deal with objects, arrays, tuples, etc increase in difficulty as you work through them.

Once you complete your work, you may open a PR with your solutions. Opening a PR will automatically run the unit tests and TypeScript compiler to check your solutions.

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
npm run tsc
```
Once you have the files transpiled, you can see how the TypeScript compiler converts them to JavaScript by inspecting the JavaScript files in your `src/js` directory.
