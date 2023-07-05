import { getId, setId } from '../number';

test('getId is 0', () => {
	expect(getId()).toBe(0);
});

test('getId is 0', () => {
	setId(0);
	expect(getId()).toBe(0);
});

test('getId is 0', () => {
	setId(-11);
	expect(getId()).toBe(0);
});

test('getId is 42', () => {
	setId(42);
	expect(getId()).toBe(42);
});

test('getId is 0', () => {
	setId('unittest');
	expect(getId()).toBe(0);
});
