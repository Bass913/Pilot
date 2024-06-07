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

	if (user.roles.includes("ROLE_SUPERADMIN")) {
		if (model === "service") model = "companyService";
	}

	const navigate = useNavigate();

	useEffect(() => {
		if (!user) {
			navigate("/auth/login");
		}
	}, [user, navigate]);

	if (!user) {
		return null;
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
