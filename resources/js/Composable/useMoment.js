import moment from "moment";

export function useMoment (date, format){
    moment(date).format(format);
}
