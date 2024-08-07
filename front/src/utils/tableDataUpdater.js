import { formatDate } from "./dateFormatter";
import { formatPrice } from "./priceFormatter";

const roles = ["ROLE_SUPERADMIN", "ROLE_ADMIN", "ROLE_EMPLOYEE", "ROLE_USER"];

const getHighestRole = (userRoles) => {
	for (let role of roles) {
		if (userRoles.includes(role)) {
			return role.replace("ROLE_", "").toLowerCase();
		}
	}
	return null;
};

export const getValue = (row, col) => {
	const dateColumns = ["date", "createdAt", "updatedAt", "startDate"];

	if (dateColumns.includes(col)) {
		return formatDate(row[col]);
	}

	if (col === "totalAmount" || col === "price") {
		return formatPrice(row[col]);
	}

	if (col === "user") {
		return row[col].id;
	}

	if (col === "employee") {
		return row[col].firstname + " " + row[col].lastname;
	}

	if (col === "images") {

		if (!row[col]) {
			return "/no-image.jpeg";
		}
		return row[col][0];
	}

	if (col === "duration") {
		return row[col] + " min";
	}

	if (col === "service.name") {
		return row[col];
	}

	if (col === "roles") {
		// return row[col].join(", ").replace(/ROLE_/g, "").toLowerCase();
		return getHighestRole(row[col]);
	}

	return row[col];
};
