const columnsToShow = {
	provider: ["images", "name", "address", "city", "zipcode"],
	service: ["name"],
	companyService: ["service.name"],
	employee: ["firstname", "lastname", "email", "phone"],
	request: [],
	user: ["firstname", "lastname", "email", "phone", "roles"],
	booking: ["date", "totalAmount", "user"],
	schedule: ["date", "employee", "service"],
};

export default columnsToShow;
