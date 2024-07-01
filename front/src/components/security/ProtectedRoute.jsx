import React, { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useUser } from "../../hooks/useUser";
import UnauthorizedPage from "../../pages/error/UnauthorizedPage";

const ProtectedRoute = ({
    element: Component,
    allowedRoles,
    model,
    ...rest
}) => {
    const { user } = useUser();

    const navigate = useNavigate();

    useEffect(() => {
        if (!user) {
            navigate("/auth/login");
        }
    }, [user, navigate]);

    if (!user) {
        return null;
    }

    if (
        // Component.name === "DynamicEntityPage" &&
        !user.roles.includes("ROLE_SUPERADMIN")
    ) {
        if (user.roles.includes("ROLE_ADMIN")) {
            if (model === "service") model = "companiesService";
			if (model === "booking") model = "companiesBooking";
			if (model === "schedule") model = "companiesSchedule";
			if (model === "employee") model = "companiesEmployee";
			if (model === "provider") model = "companiesProvider";
        } else {
            if (model === "service") model = "companyService";
            if (model === "booking") model = "companyBooking";
            if (model === "schedule") model = "companySchedule";
            if (model === "employee") model = "companyEmployee";
            if (model === "provider") model = "companyProvider";
        }
    }

    const hasAllowedRole = allowedRoles.some((allowedRole) =>
        user.roles.includes(allowedRole),
    );
    if (allowedRoles && !hasAllowedRole) {
        return <UnauthorizedPage />;
    }

    return <Component allowedRoles={allowedRoles} model={model} {...rest} />;
};

export default ProtectedRoute;
