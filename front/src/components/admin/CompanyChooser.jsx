import React from "react";
import { useUser } from "../../hooks/useUser";

function CompanyChooser({
    companies = [],
    selectedCompany = null,
    onCompanySelect,
}) {
    const { user } = useUser();
    const isSuperAdmin = user.roles.includes("ROLE_SUPERADMIN");

    const isCompanySelected = (company) => {
        return (
            (!selectedCompany && !company) ||
            (selectedCompany &&
                company &&
                selectedCompany["@id"] === company["@id"])
        );
    };

    const handleSelectChange = (event) => {
        const selectedCompanyId = event.target.value;
        const selectedCompany = companies.find(
            (company) => company["@id"] === selectedCompanyId,
        );
        onCompanySelect(selectedCompany);
    };

    return (
        <div className="mt-4">
            {isSuperAdmin ? (
                <select
                    value={selectedCompany ? selectedCompany["@id"] : ""}
                    onChange={handleSelectChange}
                    className="p-2 border rounded-lg w-full"
                >
                    <option value="">
                        {/* Option vide pour le cas non sélectionné */}
                    </option>
                    {companies.map((company) => (
                        <option key={company["@id"]} value={company["@id"]}>
                            {company.name}
                        </option>
                    ))}
                </select>
            ) : (
                <div className="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                    {companies.map((company, index) => (
                        <div
                            key={index}
                            className={`p-4 rounded-lg hover:bg-gray-100 cursor-pointer border flex items-center gap-4
                                ${isCompanySelected(company) ? "border-primary-900 bg-gray-50" : "bg-white border-gray-200"}`}
                            onClick={() => onCompanySelect(company)}
                        >
                            <div className="flex items-center gap-2">
                                {company && (
                                    <div
                                        className="w-10 h-10 bg-primary-900 rounded-full flex items-center justify-center"
                                        style={{
                                            backgroundImage: `url(${company.images ? company.images[0] : "/no-image.jpeg"})`,
                                            backgroundSize: "cover",
                                        }}
                                    ></div>
                                )}
                                <label className="text-gray-800 font-light cursor-pointer">
                                    {`${company?.name}`}
                                </label>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

export default CompanyChooser;
