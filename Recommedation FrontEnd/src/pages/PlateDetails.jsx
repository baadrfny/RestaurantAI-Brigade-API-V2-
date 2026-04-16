import { useParams } from "react-router-dom";

export default function PlateDetails(){

    const { id } = useParams();
    return(
        <div>
            <h2>This is Page Details</h2>
            <p>Plate id is : {id}</p>
        </div>
    )
}