import { useState } from "react";
import { Link } from "react-router-dom";

export default function PlateCard({
  id,
  name,
  price,
  is_available,
  description,
  onSelect,
}) {
  const [available, setAvailable] = useState(is_available);
  const [selected, setSelected] = useState(false);
  
  function handleClick() {
    const newSelected = !selected;
    setSelected(newSelected);
    onSelect(newSelected);
  }

  return (
    <div style={{ border: "1px solid gray", padding: "10px", margin: "10px" }}>
      <h2>{name}</h2>
      <p>{price} MAD</p>
      <p>{description}</p>

      {available ? (
        <span style={{ color: "green" }}>Disponible</span>
      ) : (
        <span style={{ color: "red" }}>Indisponible</span>
      )}

      <br />
      <br />

      <button onClick={() => setAvailable(!available)}>
        Changer la disponibilité
      </button>

      <br />
      <br />

      <button onClick={handleClick}>
        {selected ? "Désélectionner" : "Sélectionner"}
      </button>

      <br />

      <Link to={`/plates/${id}`} style={{ marginTop: "5px", display: "inline-block" , color: "yellow" }}>See Details</Link>

      {selected && <p>Ce plat est sélectionné</p>}

      <p>-------------------------------------------</p>
    </div>
  );
}