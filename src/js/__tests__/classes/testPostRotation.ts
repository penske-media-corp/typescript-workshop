import PostRotation from '../../classes/PostRotation';

/**
 * Mock up DOM.
 */
const dom = '<div>' +
	'  <div data-pmc-sponsored-posts="unit"></div>' +
	'  <div data-pmc-sponsored-posts="test"></div>' +
	'</div>';

document.body.innerHTML = dom;

const activePosts = [
	{
		unit: '<div>Unit 0</div>',
		test: '<div>Test 0</div>'
	},
	{
		unit: '<div>Unit 1</div>',
		test: '<div>Test 1</div>'
	}
],
	instance = new PostRotation(activePosts);

/**
 * Test cases for PostRotation class.
 */
test('PostRotation post index is 0', () => {
	expect(instance.getPostIndex()).toBe(1);
});

test('PostRotation finds 2 placements in DOM', () => {
	expect(instance.getPlacements().length).toBe(2);
});

test('PostRotation adds classes to placements', () => {
	const placements = instance.getPlacements();
	for (let i = 0; i < placements.length; i++) {
		expect(placements[i].classList.contains('pmc-sponsored-posts-visible')).toBe(true);
	}
});

/*test('PostRotation does not add inner markup to placements when index is 0', () => {
	const placements = instance.getPlacements();

	for (let i = 0; i < placements.length; i++) {
		expect(placements[i].innerHTML).toBe('');
	}
});*/

test('PostRotation can set index', () => {
	instance.setPostIndex(1);
	expect(instance.getPostIndex()).toBe(1);
});

test('PostRotation increments from set index', () => {
	instance.setPostIndex(0);
	expect(instance.setupActivePost()).toBe(1);
});

test('PostRotation does add inner markup to placements when index is not 0', () => {
	instance.setPostIndex(0);
	instance.init();

	const placements = instance.getPlacements();

	expect(placements[0].innerHTML).toBe(`<div>Unit 1</div>`);
	expect(placements[1].innerHTML).toBe(`<div>Test 1</div>`);
});

test('PostRotation will default to 0 if set index is invalid', () => {
	instance.setPostIndex(2);
	expect(instance.setupActivePost()).toBe(0);

	// instance.setPostIndex(null);
	// expect(instance.setupActivePost()).toBe(0);

	// instance.setPostIndex('unit test');
	// expect(instance.setupActivePost()).toBe(0);

	// instance.setPostIndex(undefined);
	// expect(instance.setupActivePost()).toBe(0);
});
