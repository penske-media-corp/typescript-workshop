// Employee tuple: [id, name, email, title];  
type Employee = [number, string, string, string?];

// Paycheck tuple: [Employee, tuple of two numbers [hourlyRate, hoursWorked]].
type PayCheck = [Employee, [number, number]];

/**

Calculates the gross pay for an employee based on their hourly rate and hours worked.

This function takes a pay stub for an employee and calculates the gross pay.

The employee's information is stored in a tuple, where the first element is the employee's ID,

the second element is their name, the third element is their email, and the fourth element is their title (optional).

The pay stub is also stored in a tuple, where the first element is the employee tuple and the second element is a tuple of two numbers [hourlyRate, hoursWorked].

The function returns a formatted string containing the pay stub information, including the employee's name, ID, email, title (if available), hours worked, hourly rate, and gross pay.

@param employeePayStub - A tuple containing the employee's information and the payment details.

@returns A formatted string representing the pay stub for the employee.
*/
export function calculatePayStubForEmployee(employeePayStub: [Employee, [number, number]]) {
    // Extract the employee tuple and payment details from the pay stub
    const employee = employeePayStub[0];
    const [hourlyRate, hoursWorked] = employeePayStub[1];

    // Calculate the gross pay based on the hourly rate and hours worked
    const grossPay = hourlyRate * hoursWorked;

    // Create a formatted string representing the pay stub
    const paystub = `Paystub for ${employee[1]}:\n\n` +
        `Employee ID: ${employee[0]}\n` +
        `Email: ${employee[2]}\n` +
        `Title: ${employee[3] ? employee[3] : 'N/A'}\n\n` +
        `Hours Worked: ${hoursWorked}\n` +
        `Hourly Rate: $${hourlyRate.toFixed(2)}\n` +
        `Gross Pay: $${grossPay.toFixed(2)}`;

    return paystub;
}

