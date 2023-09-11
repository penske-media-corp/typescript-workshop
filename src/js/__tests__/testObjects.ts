import { yearsInSports, simoneBilesTimeline } from '../objects';

test('Test with all parameters', () => {
    const player = {
        "currentAge": 30,
        "ageBegan": 10,
        "sport": 'football',
    };

	expect(yearsInSports(player)).toBe('This athlete has played football for 20 years');
});

test('Test without sports specified', () => {
    const player = {
        "currentAge": 60,
        "ageBegan": 44,
        "sport": null,
    };

	expect(yearsInSports(player)).toBe('This athlete has played sports for 16 years');
});

test('Test with extended simone_biles object', () => {
	expect(simoneBilesTimeline()).toBe('Simone Biles has won 4 gold medals in gymnastics over 20 years');
});