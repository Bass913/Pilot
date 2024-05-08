import { useState } from "react";

function EmployeeChooser({ employees, onEmployeeSelect }) {
	const [selectedEmployee, setSelectedEmployee] = useState(null);

	const handleEmployeeSelection = (employee) => {
		setSelectedEmployee(employee);
		onEmployeeSelect(employee);
	};

	return (
		<div className="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
			{employees.map((employee, index) => (
				<div
					key={index}
					className={`bg-white p-4 rounded-lg hover:bg-gray-50 cursor-pointer border flex items-center gap-4
                        ${selectedEmployee === employee ? "border-primary-900" : "border-gray-200"}`}
					onClick={() => handleEmployeeSelection(employee)}
				>
					<div className="flex items-center gap-2">
						{employee && (
							<div className="w-8 h-8 bg-primary-900 rounded-full flex items-center justify-center">
								<p className="text-white text-center text-sm">
									{employee.name[0]}
								</p>
							</div>
						)}
						<label className="text-gray-800 font-light cursor-pointer">
							{employee?.name || "Aucune préférence"}
						</label>
					</div>
				</div>
			))}
		</div>
	);
}

export default EmployeeChooser;
