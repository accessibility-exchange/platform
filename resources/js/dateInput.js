import { DateTime } from "luxon";

export default () => ({
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
    componentsToDate() {
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
    },
    dateToComponents(date) {
        if (date) {
            let year, month, day;
            [year, month, day] = date.split("-");
            const dt = DateTime.fromObject({ month, day, year });
            if (dt.isValid) {
                this.date = date;
                this.dateTime = dt;
                this.year = year;
                this.month = month;
                this.day = day;
            } else {
                this.date = "";
                this.dateTime = false;
            }
        }
    }
});
