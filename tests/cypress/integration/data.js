export const testUser = {
    name: "User Test",
    email: "user_test@test.com",
    password: "password",
    confirmPassword: "password",
};
export const newCost = {
    description: "Test Cost",
    cost: 100,
    unit: "none",
    periodicity: "monthly",
};
export const newClient = {
    name: "Client Test",
    email: "client_test@test.com",
    company: "Test Company",
};
export const newBudget = {
    client: newClient.name,
    cost: newCost.description,
    taxes: 21,
    discount: 10,
    total: 653.40,
};
