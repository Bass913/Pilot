import { useEffect, useState } from "react";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";
import Table from "../../components/admin/Table";
import entitiesNames from "../../lib/entitiesNames";
import { Link, useLocation } from "react-router-dom";
import Loader from "../../components/Loader";
import { useUser } from "../../hooks/useUser";
import { PlusIcon } from "@heroicons/react/24/outline";

function DynamicEntityPage({ model }) {
    const location = useLocation();
    const [data, setData] = useState([]);
    const { t } = useTranslation();
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const { user } = useUser();

    const fetchData = async () => {
        try {
            let response;
            setLoading(true);
            switch (model) {
                case "request":
                    response = await apiService.getCompanies({
                        search: null,
                        page,
                    });
                    break;
                case "provider":
                    response = await apiService.getCompanies({
                        search: null,
                        page,
                    });
                    break;
                case "companyProvider":
                    response = await apiService.getAdminCompanies(user.id);
                    break;
                case "companiesProvider":
                    response = {
                        data: {
                            "hydra:totalItems": 0,
                            "hydra:member": [],
                        },
                    };
                    for (const companyId of user.companies) {
                        const subResponse =
                            await apiService.getCompany(companyId);
                        response.data["hydra:member"].push(subResponse.data);
                        response.data["hydra:totalItems"]++;
                    }
                    break;
                case "user":
                    response = await apiService.getUsers({ page });
                    break;
                case "service":
                    response = await apiService.getServices({ page });
                    break;
                case "companyService":
                    response = await apiService.getCompanyServices({
                        companyId: user.companyId,
                        page,
                    });
                    break;
                case "companiesService":
                    response = {
                        data: {
                            "hydra:totalItems": 0,
                            "hydra:member": [],
                        },
                    };
                    for (const companyId of user.companies) {
                        const subResponse = await apiService.getCompanyServices(
                            {
                                companyId,
                                page,
                            },
                        );
                        const companyResponse =
                            await apiService.getCompany(companyId);

                        subResponse.data["hydra:member"].forEach((sub) => {
                            sub.company = companyResponse.data;
                        });

                        response.data["hydra:member"].push(
                            ...subResponse.data["hydra:member"],
                        );

                        response.data["hydra:totalItems"] +=
                            subResponse.data["hydra:totalItems"];
                    }
                    break;
                case "employee":
                    response = await apiService.getUsers({ page }); // to change to getEmployees
                    break;
                case "companyEmployee":
                    response = await apiService.getCompanyEmployees({
                        companyId: user.companyId,
                        page,
                    });
                    break;
                case "companiesEmployee":
                    response = {
                        data: {
                            "hydra:totalItems": 0,
                            "hydra:member": [],
                        },
                    };
                    for (const companyId of user.companies) {
                        const subResponse =
                            await apiService.getCompanyEmployees({ companyId });

                        const companyResponse =
                            await apiService.getCompany(companyId);

                        subResponse.data["hydra:member"].forEach((sub) => {
                            sub.company = companyResponse.data;
                        });

                        response.data["hydra:member"].push(
                            ...subResponse.data["hydra:member"],
                        );

                        response.data["hydra:totalItems"] +=
                            subResponse.data["hydra:totalItems"];
                    }
                    break;
                case "booking":
                    response = await apiService.getBookings({ page });
                    break;
                case "companyBooking":
                    response = await apiService.getCompanyBookings({
                        companyId: user.companyId,
                        page,
                    });
                    break;
                case "companiesBooking":
                    response = {
                        data: {
                            "hydra:totalItems": 0,
                            "hydra:member": [],
                        },
                    };
                    for (const companyId of user.companies) {
                        const subResponse = await apiService.getCompanyBookings(
                            {
                                companyId,
                                page,
                            },
                        );
                        const companyResponse =
                            await apiService.getCompany(companyId);

                        subResponse.data["hydra:member"].forEach((sub) => {
                            sub.company = companyResponse.data;
                            sub.employee = {
                                ...sub.employee,
                                name: `${sub.employee.firstname} ${sub.employee.lastname}`,
                            };
                            sub.client = {
                                ...sub.client,
                                name: `${sub.client.firstname} ${sub.client.lastname}`,
                            };
                        });

                        response.data["hydra:member"].push(
                            ...subResponse.data["hydra:member"],
                        );

                        response.data["hydra:totalItems"] +=
                            subResponse.data["hydra:totalItems"];
                    }
                    console.log(response);
                    break;
                default:
                    break;
            }
            setData(response.data);
        } catch (error) {
            console.error("Error while fetching data:", error);
            setData([]);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchData();
    }, [location.pathname, page]);

    const showCreationButton =
        ((user.roles.includes("ROLE_SUPERADMIN") ||
            user.roles.includes("ROLE_ADMIN")) &&
            model === "companyProvider") ||
        model === "companiesProvider" ||
        model === "companyService" ||
        model === "companiesService" ||
        model === "companyEmployee" ||
        model === "companiesEmployee";

    return (
        <DashboardLayout>
            <div className="flex justify-between items-center">
                <h1 className="text-xl font-medium text-gray-900 mb-4 capitalize">
                    {t(entitiesNames[model].labelPlural)}
                </h1>
                {showCreationButton && (
                    <Link to={`create`}>
                        <button className="rounded-full bg-primary-600 text-white px-2 py-2 flex items-center space-x-2 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <PlusIcon className="h-5 w-5" />
                        </button>
                    </Link>
                )}
            </div>
            {loading ? (
                <div className="flex justify-center h-96 items-center">
                    <Loader />
                </div>
            ) : (
                <Table
                    data={data}
                    model={model}
                    page={page}
                    onChangePage={setPage}
                />
            )}
        </DashboardLayout>
    );
}

export default DynamicEntityPage;
