const columnsToShow = {
	provider: ["images", "name", "address", "city", "zipcode"],
	service: ["name"],
	companyService: ["service.name", "duration", "price"],
	employee: ["firstname", "lastname", "email", "phone"],
	companyEmployee: ["firstname", "lastname", "email", "phone"],
	request: [],
	user: ["firstname", "lastname", "email", "phone", "roles"],
	booking: ["startDate", "companyService.service.name", "employee"],
	companyBooking: ["date", "totalAmount", "user"],
	schedule: ["date", "employee", "service"],
	companySchedule: ["date", "employee", "service"],
};

export default columnsToShow;
