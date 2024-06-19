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

	if (!user.roles.includes("ROLE_SUPERADMIN")) {
		if (model === "service") model = "companyService";
		if (model === "booking") model = "companyBooking";
		if (model === "schedule") model = "companySchedule";
		if (model === "employee") model = "companyEmployee";
	}

	const hasAllowedRole = allowedRoles.some((allowedRole) =>
		user.roles.includes(allowedRole)
	);
	if (allowedRoles && !hasAllowedRole) {
		return <UnauthorizedPage />;
	}

	return <Component allowedRoles={allowedRoles} model={model} {...rest} />;
};

export default ProtectedRoute;
