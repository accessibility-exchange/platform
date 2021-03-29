require("./bootstrap");

require("alpinejs");

const { DateTime } = require("luxon");

window.dateInput = function () {
    return {
        dateTime: false,
        date: "",
        year: "",
        month: "",
        day: "",
        output() {
            if (this.dateTime) {
                if (this.dateTime.isValid) {
                    return `You have entered ${this.dateTime.toLocaleString(DateTime.DATE_FULL)}.`;
                } else {
                    return "You have entered a date that is not valid.";
                }
            }
            return "";
        },
        updateDate() {
            if (this.year && this.month && this.day) {
                const dt = DateTime.fromObject({ month: this.month, day: this.day, year: this.year });
                if (dt.isValid) {
                    this.date = dt.toSQLDate();
                    this.dateTime = dt;
                } else {
                    this.dateTime = false;
                    this.date = "";
                }
            } else {
                this.dateTime = false;
                this.date = "";
            }
        }
    };
};
