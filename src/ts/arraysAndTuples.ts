// Employee tuple: [id, name, email, title];  
type Employee = [number, string, string, string?];

// Paycheck tuple: [Employee, tuple of two numbers [hourlyRate, hoursWorked]].
type PayCheck = [Employee, [number, number]];

/**

Calculates the gross pay for an employee based on their hourly rate and hours worked.

The employee's information is stored in a tuple, where the first element is the employee's ID,

the second element is their name, the third element is their email, and the fourth element is their title (optional).

The pay stub is also stored in a tuple, where the first element is the employee tuple and the second element is a tuple of two numbers [hourlyRate, hoursWorked].

The function returns a JSON object containing the pay stub information, including the employee's name, ID, email, title (if available), hours worked, hourly rate, and gross pay.

@param {PayCheck} employeePayStub - A tuple containing the employee's information and the payment details.

@returns {object} A JSON object representing fields for the employee's pay stub.
*/
export function calculatePayStubForEmployee(employeePayStub: PayCheck): object {
    // Extract the employee tuple and payment details from the pay stub
    const employee = employeePayStub[0];
    const [hourlyRate, hoursWorked] = employeePayStub[1];

    // Calculate the gross pay based on the hourly rate and hours worked
    const grossPay = hourlyRate * hoursWorked;

    // Create a JSON object representing the pay stub
	const paystub = {
		employeeName: employee[ 1 ],
		employeeID: employee[ 0 ],
		email: employee[ 2 ],
		title: employee[ 3 ] ? employee[ 3 ] : 'N/A',
		hoursWorked: hoursWorked,
		rate: hourlyRate,
		grossPay: grossPay,
	};

    return paystub;
}

