import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Select from "react-select";

import apiService from "../../services/apiService";
import DashboardLayout from "../../layouts/DashboardLayout";
import { useTranslation } from "react-i18next";
import entitiesNames from "../../lib/entitiesNames";
import Loader from "../../components/Loader";
import BackButton from "../../components/BackButton";
import { useUser } from "../../hooks/useUser";

function EntityCreatePage({ model }) {
    const { t } = useTranslation();
    const [entityData, setEntityData] = useState({
        firstname: "",
        lastname: "",
        email: "",
        phone: "",
        name: "",
        description: "",
        address: "",
        city: "",
        zipcode: "",
        speciality: null,
        price: "",
        company: [],
        service: [],
        duration: "",
    });
    const [loading, setLoading] = useState(false);
    const [companies, setCompanies] = useState([]);
    const [services, setServices] = useState([]);
    const [specialties, setSpecialties] = useState([]);
    const [prestations, setPrestations] = useState([]);
    const [newPrestation, setNewPrestation] = useState({
        service: null,
        duration: "",
        price: "",
    });
    const navigate = useNavigate();
    const { user } = useUser();

    useEffect(() => {
        const fetchCompanies = async () => {
            if (user) {
                try {
                    const response = await apiService.getAdminCompanies(
                        user.id,
                    );
                    setCompanies(response.data.companies);
                } catch (error) {
                    console.error("Failed to fetch companies:", error);
                }
            }
        };

        const fetchServices = async () => {
            try {
                const response = await apiService.getServices({
                    pagination: false,
                });
                setServices(response.data["hydra:member"]);
            } catch (error) {
                console.error("Failed to fetch services:", error);
            }
        };

        const fetchSpecialties = async () => {
            try {
                const response = await apiService.getSpecialities({
                    pagination: false,
                });
                setSpecialties(response.data["hydra:member"]);
            } catch (error) {
                console.error("Failed to fetch specialties:", error);
            }
        };

        if (model === "service") fetchCompanies();
        if (
            ["company", "companyProvider", "companiesProvider"].includes(model)
        ) {
            fetchServices();
            fetchSpecialties();
        }
    }, [user, model]);

    useEffect(() => {
        const fetchServices = async () => {
            if (entityData.company && entityData.company.length > 0) {
                try {
                    const newServices = [];
                    for (let i = 0; i < entityData.company.length; i++) {
                        const companyId = entityData.company[i]["@id"]
                            .split("/")
                            .pop();
                        const response = await apiService.getCompanyServices({
                            companyId,
                        });
                        newServices.push(...response.data.services);
                    }
                    setServices(newServices);
                } catch (error) {
                    console.error("Failed to fetch services:", error);
                }
            }
        };

        fetchServices();
    }, [entityData.company]);

    const handleSubmit = async (newData) => {
        try {
            setLoading(true);
            let response;
            switch (model) {
                case "provider":
                case "companyProvider":
                case "companiesProvider":
                    const modifiedData = {
                        ...newData,
                        companyServices: prestations.map((prestation) => ({
                            service: prestation.service["@id"],
                            duration: parseInt(prestation.duration),
                            price: prestation.price,
                        })),
                        speciality: newData.speciality["@id"],
                    };
                    response = await apiService.createCompany(modifiedData);
                    break;
                case "user":
                case "employee":
                case "companiesEmployee":
                    response = await apiService.adminCreateUser(newData);
                    break;
                case "service":
                    response = await apiService.createService(newData);
                    break;
                case "companyService":
                case "companiesService":
                    response = await apiService.createCompanyService(newData);
                    break;
                case "booking":
                    response = await apiService.createBooking(newData);
                    break;
                default:
                    break;
            }
            // TODO: Add notification
        } catch (error) {
            console.error(`Failed to create ${model}:`, error);
        } finally {
            setLoading(false);
        }
    };

    const handleAddPrestation = () => {
        if (
            newPrestation.service &&
            newPrestation.duration &&
            newPrestation.price
        ) {
            setPrestations([...prestations, newPrestation]);
            setNewPrestation({ service: null, duration: "", price: "" });
        }
    };

    useEffect(() => {
        setEntityData({
            ...entityData,
            companyServices: prestations.map((prestation) => ({
                service: prestation.service["@id"],
                duration: parseInt(prestation.duration),
                price: prestation.price,
            })),
        });
    }, [prestations]);

    useEffect(() => {
        console.log(entityData);
    }, [entityData]);

    const handleRemovePrestation = (index) => {
        setPrestations(prestations.filter((_, i) => i !== index));
    };

    const formFields = [
        // User / Employee
        {
            name: "firstname",
            label: t("firstname"),
            type: "text",
            value: entityData.firstname || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "lastname",
            label: t("lastname"),
            type: "text",
            value: entityData.lastname || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "email",
            label: t("email"),
            type: "email",
            value: entityData.email || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },
        {
            name: "phone",
            label: t("phone"),
            type: "tel",
            value: entityData.phone || "",
            models: [
                "user",
                "employee",
                "companyEmployee",
                "companiesEmployee",
            ],
        },

        // Company
        {
            name: "name",
            label: t("name"),
            type: "text",
            value: entityData.name || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "description",
            label: t("description"),
            type: "textarea",
            value: entityData.description || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "address",
            label: t("address"),
            type: "text",
            value: entityData.address || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "city",
            label: t("city"),
            type: "text",
            value: entityData.city || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "zipcode",
            label: t("zipcode"),
            type: "text",
            value: entityData.zipcode || "",
            models: ["provider", "companyProvider", "companiesProvider"],
        },
        {
            name: "speciality",
            label: t("speciality"),
            type: "select",
            options: specialties,
            value: entityData.speciality || null,
            models: ["provider", "companyProvider", "companiesProvider"],
        },

        // Service
        {
            name: "price",
            label: t("price"),
            type: "number",
            value: entityData.price || "",
            step: 0.01,
            models: ["service", "companyService", "companiesService"],
        },
        {
            name: "company",
            label: t("company"),
            type: "multi-select",
            value: entityData.company || [],
            models: ["service", "companyService", "companiesService"],
        },
        {
            name: "service",
            label: t("service"),
            type: "multi-select",
            value: entityData.service || [],
            models: ["service", "companyService", "companiesService"],
        },
        {
            name: "duration",
            label: t("duration"),
            type: "number",
            value: entityData.duration || "",
            step: 1,
            models: ["service", "companyService", "companiesService"],
        },
    ];

    const getCommonFields = () => {
        return formFields.filter((field) => field.models.includes(model));
    };

    const commonFields = getCommonFields();

    const newData = commonFields.reduce((acc, field) => {
        acc[field.name] =
            field.type === "number" && field.step === 1
                ? parseInt(field.value)
                : field.value;
        return acc;
    }, {});

    const handleSelectChange = (selectedOption, field) => {
        if (field.type === "multi-select") {
            setEntityData({
                ...entityData,
                [field.name]: selectedOption
                    ? selectedOption.map((option) =>
                          field.options.find((opt) => opt.id === option.value),
                      )
                    : [],
            });
        } else if (field.type === "select") {
            const selected = field.options.find(
                (opt) => opt.id === selectedOption.value,
            );
            setEntityData({
                ...entityData,
                [field.name]: selectedOption ? selected : null,
            });
        }
    };

    return (
        <DashboardLayout>
            <div className="flex justify-between">
                <h1 className="text-xl font-medium text-gray-900 mb-6 capitalize">
                    {t(entitiesNames[model].label)}
                </h1>
                <BackButton to={`/admin/${entitiesNames[model].labelPlural}`} />
            </div>
            {loading ? (
                <div className="flex justify-center h-96 items-center">
                    <Loader />
                </div>
            ) : (
                <div>
                    <div className="space-y-6">
                        {commonFields.map((field) => (
                            <div key={field.name} className="mb-4">
                                <label
                                    htmlFor={field.name}
                                    className="block text-sm font-medium text-gray-700"
                                >
                                    {field.label}
                                </label>
                                {field.type === "multi-select" ||
                                field.type === "select" ? (
                                    <Select
                                        id={field.name}
                                        name={field.name}
                                        value={
                                            field.value && field.value["@id"]
                                                ? {
                                                      value: field.value["@id"],
                                                      label: t(
                                                          field.value.name,
                                                      ),
                                                  }
                                                : field.value
                                                  ? field.value.map(
                                                        (value) => ({
                                                            value: value.id,
                                                            label: t(
                                                                value.name,
                                                            ),
                                                        }),
                                                    )
                                                  : null
                                        }
                                        className="mt-1 block w-full rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        onChange={(selectedOption) =>
                                            handleSelectChange(
                                                selectedOption,
                                                field,
                                            )
                                        }
                                        options={
                                            field.options
                                                ? field.options.map(
                                                      (option) => ({
                                                          value: option.id,
                                                          label: t(option.name),
                                                      }),
                                                  )
                                                : []
                                        }
                                        isMulti={field.type === "multi-select"}
                                    />
                                ) : (
                                    <input
                                        type={field.type}
                                        id={field.name}
                                        name={field.name}
                                        value={field.value}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        onChange={(e) =>
                                            setEntityData({
                                                ...entityData,
                                                [field.name]: e.target.value,
                                            })
                                        }
                                        step={field.step}
                                    />
                                )}
                            </div>
                        ))}
                    </div>

                    {model === "provider" ||
                    model === "companyProvider" ||
                    model === "companiesProvider" ? (
                        <div className="mt-8">
                            <h2 className="text-lg font-medium text-gray-900">
                                {t("Prestations")}
                            </h2>
                            <div className="mt-4 grid grid-cols-1 gap-y-3 gap-x-4 sm:grid-cols-3">
                                <div className="mb-4">
                                    <label
                                        htmlFor="newService"
                                        className="block text-sm font-medium text-gray-700"
                                    >
                                        {t("Service")}
                                    </label>
                                    <Select
                                        id="newService"
                                        name="newService"
                                        value={
                                            newPrestation.service
                                                ? {
                                                      value: newPrestation
                                                          .service.id,
                                                      label: t(
                                                          newPrestation.service
                                                              .name,
                                                      ),
                                                  }
                                                : null
                                        }
                                        className="mt-1 block w-full rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        onChange={(selectedOption) =>
                                            setNewPrestation({
                                                ...newPrestation,
                                                service: services.find(
                                                    (service) =>
                                                        service.id ===
                                                        selectedOption.value,
                                                ),
                                            })
                                        }
                                        options={services.map((service) => ({
                                            value: service.id,
                                            label: t(service.name),
                                        }))}
                                    />
                                </div>
                                <div className="mb-4">
                                    <label
                                        htmlFor="newDuration"
                                        className="block text-sm font-medium text-gray-700"
                                    >
                                        {t("Duration")}
                                    </label>
                                    <input
                                        type="number"
                                        id="newDuration"
                                        name="newDuration"
                                        value={newPrestation.duration}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        onChange={(e) =>
                                            setNewPrestation({
                                                ...newPrestation,
                                                duration: e.target.value,
                                            })
                                        }
                                        step="1"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label
                                        htmlFor="newPrice"
                                        className="block text-sm font-medium text-gray-700"
                                    >
                                        {t("Price")}
                                    </label>
                                    <input
                                        type="number"
                                        id="newPrice"
                                        name="newPrice"
                                        value={newPrestation.price}
                                        className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        onChange={(e) =>
                                            setNewPrestation({
                                                ...newPrestation,
                                                price: e.target.value,
                                            })
                                        }
                                        step="0.01"
                                    />
                                </div>
                            </div>
                            <button
                                onClick={handleAddPrestation}
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                {t("Add Prestation")}
                            </button>

                            {prestations.length > 0 && (
                                <div className="mt-8">
                                    <h2 className="text-lg font-medium text-gray-900">
                                        {t("Added Prestations")}
                                    </h2>
                                    <ul className="mt-4 space-y-2">
                                        {prestations.map(
                                            (prestation, index) => (
                                                <li
                                                    key={index}
                                                    className="flex justify-between items-center bg-white px-4 py-2 border rounded-md"
                                                >
                                                    <div>
                                                        <p>
                                                            {t("Service")}:{" "}
                                                            {t(
                                                                prestation
                                                                    .service
                                                                    .name,
                                                            )}
                                                        </p>
                                                        <p>
                                                            {t("duration")}:{" "}
                                                            {
                                                                prestation.duration
                                                            }{" "}
                                                            {t("minutes")}
                                                        </p>
                                                        <p>
                                                            {t("price")}:{" "}
                                                            {prestation.price} €
                                                        </p>
                                                    </div>
                                                    <button
                                                        onClick={() =>
                                                            handleRemovePrestation(
                                                                index,
                                                            )
                                                        }
                                                        className="inline-flex items-center px-2 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    >
                                                        {t("Remove")}
                                                    </button>
                                                </li>
                                            ),
                                        )}
                                    </ul>
                                </div>
                            )}
                        </div>
                    ) : null}
                </div>
            )}
            <button
                onClick={() => handleSubmit(newData)}
                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 flex justify-center mt-8"
            >
                {t("create")}
            </button>
        </DashboardLayout>
    );
}

export default EntityCreatePage;
