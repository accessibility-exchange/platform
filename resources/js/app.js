require("./bootstrap");

import Alpine from "alpinejs";

import confirmPassword from "./confirmPassword.js";

Alpine.data("confirmPassword", confirmPassword);

Alpine.start();
