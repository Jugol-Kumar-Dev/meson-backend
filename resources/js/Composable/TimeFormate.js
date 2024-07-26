import moment from "moment";


export default function date(date, format){
    return moment(date).format(format);
}
