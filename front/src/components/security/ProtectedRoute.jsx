import React, { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useUser } from "../../hooks/useUser";
import UnauthorizedPage from "../../pages/error/UnauthorizedPage";

const ProtectedRoute = ({ element: Component, allowedRoles, ...rest }) => {
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

	const hasAllowedRole = allowedRoles.some((allowedRole) =>
		user.roles.includes(allowedRole)
	);
	if (allowedRoles && !hasAllowedRole) {
		return <UnauthorizedPage />;
	}

	return <Component allowedRoles={allowedRoles} {...rest} />;
};

export default ProtectedRoute;
