const columnsToShow = {
	provider: ["id", "images", "name", "address", "city", "zipcode"],
	companyProvider: ["id", "images", "name", "address", "city", "zipcode"],
	service: ["id", "name"],
	companyService: ["id", "service.name", "duration", "price"],
	employee: ["id", "firstname", "lastname", "email", "phone"],
	companyEmployee: ["id", "firstname", "lastname", "email", "phone"],
	request: [],
	user: ["id", "firstname", "lastname", "email", "phone", "roles"],
	booking: ["id", "startDate", "companyService.service.name", "employee"],
	companyBooking: ["id", "date", "totalAmount", "user"],
	schedule: ["id", "date", "employee", "service"],
	companySchedule: ["id", "date", "employee", "service"],
};

export default columnsToShow;
