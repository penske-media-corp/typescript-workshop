/**
 * Defining the object type for athletes in a database.
 * 
 * @typedef {Object} athleteType
 * @property {number} currentAge - Current age of our athlete.
 * @property {number} ageBegan - Age when our athlete started playing organized sports.
 * @property {boolean} contactSport - Is their sport considered a contact sport.
 * @property {string | null} sport - Name of sport played.
 * /

/**
 * Calculate how long an athlete has been playing their sport.
 * 
 * @param {athleteType} athlete - Athlete object from database.
 * @returns {string}
 */
export function yearsInSports(athlete) {
    return `This athlete has played ${athlete.sport ? athlete.sport : 'sports'} for ${athlete.currentAge - athlete.ageBegan} years`;
}


