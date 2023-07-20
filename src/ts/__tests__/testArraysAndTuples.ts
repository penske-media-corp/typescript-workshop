import { calculatePayStubForEmployee } from '../arraysAndTuples';

test('Basic Input', () => {
    const employeePayStub = [
        [1, "John Doe", "john.doe@example.com", "Manager"], // employee tuple
        [20, 40] // payment details [hourlyRate, hoursWorked]
    ];
    const expectedOutput = `Paystub for John Doe:

Employee ID: 1
Email: john.doe@example.com
Title: Manager

Hours Worked: 40
Hourly Rate: $20.00
Gross Pay: $800.00`;

    const actualOutput = calculatePayStubForEmployee(employeePayStub);
    expect(actualOutput).toBe(expectedOutput);
});

test(' Missing Title', () => {
    const employeePayStub = [
        [1, "John Doe", "john.doe@example.com"], // employee tuple without title
        [20, 40] // payment details [hourlyRate, hoursWorked]
    ];
    const expectedOutput = `Paystub for John Doe:

Employee ID: 1
Email: john.doe@example.com
Title: N/A

Hours Worked: 40
Hourly Rate: $20.00
Gross Pay: $800.00`;

    const actualOutput = calculatePayStubForEmployee(employeePayStub);
    expect(actualOutput).toBe(expectedOutput);
});

test('Zero Hours Worked', () => {
    const employeePayStub = [
        [1, "John Doe", "john.doe@example.com", "Manager"], // employee tuple
        [20, 0] // payment details with zero hours worked
    ];
    const expectedOutput = `Paystub for John Doe:

Employee ID: 1
Email: john.doe@example.com
Title: Manager

Hours Worked: 0
Hourly Rate: $20.00
Gross Pay: $0.00`;

    const actualOutput = calculatePayStubForEmployee(employeePayStub);
    expect(actualOutput).toBe(expectedOutput);
});

test('Negative Hourly Rate', () => {
    const employeePayStub = [
        [1, "John Doe", "john.doe@example.com", "Manager"], // employee tuple
        [-20, 40] // payment details with negative hourly rate
    ];
    // Note that the gross pay should still be positive, since negative hours are not allowed
    const expectedOutput = `Paystub for John Doe:

Employee ID: 1
Email: john.doe@example.com
Title: Manager

Hours Worked: 40
Hourly Rate: $-20.00
Gross Pay: $-800.00`;

    const actualOutput = calculatePayStubForEmployee(employeePayStub);
    expect(actualOutput).toBe(expectedOutput);
});

test('Fractional Hourly Rate', () => {
    const employeePayStub = [
        [1, "John Doe", "john.doe@example.com", "Manager"], // employee tuple
        [15.5, 35] // payment details with fractional hourly rate
    ];
    const expectedOutput = `Paystub for John Doe:

Employee ID: 1
Email: john.doe@example.com
Title: Manager

Hours Worked: 35
Hourly Rate: $15.50
Gross Pay: $542.50`;

    const actualOutput = calculatePayStubForEmployee(employeePayStub);
    expect(actualOutput).toBe(expectedOutput);
});