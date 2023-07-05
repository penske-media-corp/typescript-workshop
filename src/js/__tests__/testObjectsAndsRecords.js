import { yearsInSports } from '../objectsAndRecords';

test('Test with all parameters', () => {

    const player = {
        "currentAge": 30,
        "ageBegan": 10,
        "contactSport": true,
        "sport": 'football',
    };

	expect(yearsInSports(player)).toBe('This athlete has played football for 20 years');
});

test('Test without contactSports available', () => {

    const player = {
        "currentAge": 42,
        "ageBegan": 8,
        "sport": 'baseball',
    };

	expect(yearsInSports(player)).toBe('This athlete has played baseball for 34 years');
});

test('Test without sports specified', () => {

    const player = {
        "currentAge": 60,
        "ageBegan": 44,
        "contactSport": false,
        "sport": null,
    };

	expect(yearsInSports(player)).toBe('This athlete has played sports for 16 years');
});