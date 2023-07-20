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
function calculatePayStubForEmployee(employeePayStub) {
    // Extract the employee tuple and payment details from the pay stub
    var employee = employeePayStub[0];
    var _a = employeePayStub[1], hourlyRate = _a[0], hoursWorked = _a[1];
    // Calculate the gross pay based on the hourly rate and hours worked
    var grossPay = hourlyRate * hoursWorked;
    // Create a formatted string representing the pay stub
    var paystub = "Paystub for ".concat(employee[1], ":\n\n") +
        "Employee ID: ".concat(employee[0], "\n") +
        "Email: ".concat(employee[2], "\n") +
        "Title: ".concat(employee[3] ? employee[3] : 'N/A', "\n\n") +
        "Hours Worked: ".concat(hoursWorked, "\n") +
        "Hourly Rate: $".concat(hourlyRate.toFixed(2), "\n") +
        "Gross Pay: $".concat(grossPay.toFixed(2));
    return paystub;
}
exports.calculatePayStubForEmployee = calculatePayStubForEmployee;
