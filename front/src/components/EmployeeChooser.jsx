import React from "react";
import { useTranslation } from "react-i18next";

function EmployeeChooser({
    employees,
    selectedEmployee,
    onEmployeeSelect,
    withNoPreference = true,
}) {
    const { t } = useTranslation();

    const isEmployeeSelected = (employee) => {
        return (
            (!selectedEmployee && !employee) ||
            (selectedEmployee &&
                employee &&
                selectedEmployee["@id"] === employee["@id"])
        );
    };

    return (
        <div className="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
            {(withNoPreference ? [null, ...employees] : employees).map(
                (employee, index) => (
                    <div
                        key={index}
                        className={`p-4 rounded-lg hover:bg-gray-100 cursor-pointer border flex items-center gap-4
                        ${isEmployeeSelected(employee) ? "border-primary-900 bg-gray-50" : "bg-white border-gray-200"}`}
                        onClick={() => onEmployeeSelect(employee)}
                    >
                        <div className="flex items-center gap-2">
                            {employee && (
                                <div className="w-8 h-8 bg-primary-900 rounded-full flex items-center justify-center">
                                    <p className="text-white text-center text-sm">
                                        {employee.firstname[0]}
                                    </p>
                                </div>
                            )}
                            <label className="text-gray-800 font-light cursor-pointer">
                                {employee
                                    ? `${employee?.firstname} ${employee?.lastname}`
                                    : t("no-preference")}
                            </label>
                        </div>
                    </div>
                ),
            )}
        </div>
    );
}

export default EmployeeChooser;
