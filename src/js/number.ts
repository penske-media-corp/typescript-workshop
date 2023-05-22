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
	const num = parseInt(global.localStorage.getItem(storage), 10);

	if (isNaN(num)) {
		return 0;
	}

	return num;
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
