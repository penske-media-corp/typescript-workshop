/**
 * The localStorage key.
 *
 * @type {string}
 */
const storage = 'testId';

/**
 * Helper to get the ID from localStorage.
 *
 * @returns {number}
 */
export function getId() {
	const id = parseInt(global.localStorage.getItem(storage), 10);

	return validateId(id);
}

/**
 * Helper to set the ID from localStorage.
 *
 * @param {number} id
 * @returns {void}
 */
export function setId(id) {
	global.localStorage.setItem(storage, String(id));
}

/**
 * Helper to validate the ID.
 *
 * @param {number} id
 * @returns {number}
 */
export function validateId(id) {
	if (isNaN(id) || 0 > id) {
		return 0;
	}

	return id;
}
