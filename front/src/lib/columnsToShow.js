const columnsToShow = {
	provider: ["id", "images", "name", "address", "city", "zipcode"],
	companyProvider: ["id", "images", "name", "address", "city", "zipcode"],
	companiesProvider: ["id", "images", "name", "address", "city", "zipcode"],
	service: ["id", "name"],
	companyService: ["id", "service.name", "duration", "price"],
	companiesService: ["id", "service.name", "duration", "price", "company.name"],
	employee: ["id", "firstname", "lastname", "email", "phone"],
	companyEmployee: ["id", "firstname", "lastname", "email", "phone"],
	companiesEmployee: ["id", "firstname", "lastname", "email", "phone", "company.name"],
	request: [],
	user: ["id", "firstname", "lastname", "email", "phone", "roles"],
	booking: ["id", "startDate", "companyService.service.name", "employee"],
	companyBooking: ["id", "date", "totalAmount", "user"],
	companiesBookings: ["id", "date", "totalAmount", "user", "company.name"],
	schedule: ["id", "date", "employee", "service"],
	companySchedule: ["id", "date", "employee", "service"],
};

export default columnsToShow;
