import { calculatePayStubForEmployee } from '../arraysAndTuples';

test( 'Basic Input', () => {
	const employeePayStub = [
		[ 1, 'John Doe', 'john.doe@example.com', 'Manager' ], // employee tuple
		[ 20, 40 ], // payment details [hourlyRate, hoursWorked]
	];
	const expectedOutput = {
		employeeName: 'John Doe',
		employeeID: 1,
		email: 'john.doe@example.com',
		title: 'Manager',
		hoursWorked: 40,
		rate: 20,
		grossPay: 800,
	};

	const actualOutput = calculatePayStubForEmployee( employeePayStub );
	expect( actualOutput ).toMatchObject( expectedOutput );
} );

test( 'Missing title', () => {
	const employeePayStub = [
		[ 1, 'John Doe', 'john.doe@example.com' ], // employee tuple without title
		[ 20, 40 ], // payment details [hourlyRate, hoursWorked]
	];
	const expectedOutput = {
		employeeName: 'John Doe',
		employeeID: 1,
		email: 'john.doe@example.com',
		title: 'N/A',
		hoursWorked: 40,
		rate: 20,
		grossPay: 800,
	};

	const actualOutput = calculatePayStubForEmployee( employeePayStub );
	expect( actualOutput ).toMatchObject( expectedOutput );
} );

test( 'Zero hoursWorked', () => {
	const employeePayStub = [
		[ 1, 'John Doe', 'john.doe@example.com', 'Manager' ], // employee tuple
		[ 20, 0 ], // payment details with zero hoursWorked
	];
	const expectedOutput = {
		employeeName: 'John Doe',
		employeeID: 1,
		email: 'john.doe@example.com',
		title: 'Manager',
		hoursWorked: 0,
		rate: 20,
		grossPay: 0,
	};

	const actualOutput = calculatePayStubForEmployee( employeePayStub );
	expect( actualOutput ).toMatchObject( expectedOutput );
} );

test( 'Negative rate', () => {
	const employeePayStub = [
		[ 1, 'John Doe', 'john.doe@example.com', 'Manager' ], // employee tuple
		[ -20, 40 ], // payment details with negative rate
	];
	// Note that the grossPay should still be positive, since negative hours are not allowed
	const expectedOutput = {
		employeeName: 'John Doe',
		employeeID: 1,
		email: 'john.doe@example.com',
		title: 'Manager',
		hoursWorked: 40,
		rate: -20,
		grossPay: -800,
	};

	const actualOutput = calculatePayStubForEmployee( employeePayStub );
	expect( actualOutput ).toMatchObject( expectedOutput );
} );

test( 'Fractional rate', () => {
	const employeePayStub = [
		[ 1, 'John Doe', 'john.doe@example.com', 'Manager' ], // employee tuple
		[ 15.5, 35 ], // payment details with fractional rate
	];
	const expectedOutput = {
		employeeName: 'John Doe',
		employeeID: 1,
		email: 'john.doe@example.com',
		title: 'Manager',
		hoursWorked: 35,
		rate: 15.5,
		grossPay: 542.5,
	};

	const actualOutput = calculatePayStubForEmployee( employeePayStub );
	expect( actualOutput ).toMatchObject( expectedOutput );
} );