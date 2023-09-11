// Instructions: Create a typescript type alias or interface for the athleteType object that mirrors the 
// properties of the JSDocs object below.

// Helpful resources here: https://bit.ly/3sP65Xa
// Less helpful but still interesting resource here: https://type-level-typescript.com/objects-and-records

/**
 * Defining the object type for athletes in a database.
 * @typedef {Object} athlete
 * @property {number} currentAge - Current age of our athlete.
 * @property {number} ageBegan - Age when our athlete started playing organized sports.
 * @property {string | null} sport - Name of sport played.
 */
const Generic_Athlete = {
    currentAge: 0,
    ageBegan: 0,
    sport: null,
};

/**
 * Calculate how long an athlete has been playing their sport.
 * 
 * @param {Generic_Athlete} athlete - Athlete object from database.
 * @returns {string}
 */
export function yearsInSports(athlete) {
    return `This athlete has played ${athlete.sport ? athlete.sport : 'sports'} for ${athlete.currentAge - athlete.ageBegan} years`;
}

// PART 2: Extending a type interface

// Instructions: 
// Extend a Generic_Athlete object to make a new object called 'Simone_Biles' which includes a property called 'gold_medals' which is a number type

const Simone_Biles = Object.create(Generic_Athlete);
Simone_Biles.currentAge = 26;
Simone_Biles.ageBegan = 6;
Simone_Biles.sport = 'gymnastics';

Simone_Biles.gold_medals = 4;

/**
 * Calculate how long an athlete has been playing their sport.
 * @returns {string}
 */
export function simoneBilesTimeline() {
    return `Simone Biles has won ${Simone_Biles.gold_medals} gold medals in ${Simone_Biles.sport} over ${Simone_Biles.currentAge - Simone_Biles.ageBegan} years`;
}





