import { useState, useEffect } from "react";
import PlateCard from "../components/PlateCard";
import api from "../api/axios";

function Plates() {
    // const Plats = [
    //   {
    //     id: 1,
    //     name: "Pizza",
    //     price: 50,
    //     description: "Italian pizza",
    //     is_available: true,
    //   },
    //   {
    //     id: 2,
    //     name: "Burger",
    //     price: 30,
    //     description: "Hamburger",
    //     is_available: false,
    //   },
    //   {
    //     id: 3,
    //     name: "Tajine",
    //     price: 130,
    //     description: "Maroccaine tajine",
    //     is_available: true,
    //   },
    // ];

    const [plates, setPlates] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await api.get("/plates");
                setPlates(res.data.data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, []);

    if (loading) {
        return <p>Loading ...</p>;
    }
    if (error) {
        return <p>{error}</p>;
    }

    return (
        <div
            style={{
                display: "grid",
                gridTemplateColumns: "repeat(auto-fill, minmax(280px, 1fr))",
                gap: "24px",
                padding: "40px",
                backgroundColor: "#f9f9f9",
                fontFamily: "'Inter', system-ui, sans-serif",
            }}
        >
            {plates.map((plat) => (
                <div
                    key={plat.id}
                    style={{
                        backgroundColor: "#fff",
                        padding: "24px",
                        borderRadius: "16px",
                        boxShadow: "0 4px 20px rgba(0,0,0,0.08)",
                        transition: "all 0.3s ease",
                        display: "flex",
                        flexDirection: "column",
                        justifyContent: "space-between",
                        border: "1px solid #f0f0f0",
                    }}
                    // Simple hover logic using inline events
                    onMouseEnter={(e) => {
                        e.currentTarget.style.transform = "translateY(-8px)";
                        e.currentTarget.style.boxShadow =
                            "0 12px 30px rgba(0,0,0,0.12)";
                    }}
                    onMouseLeave={(e) => {
                        e.currentTarget.style.transform = "translateY(0)";
                        e.currentTarget.style.boxShadow =
                            "0 4px 20px rgba(0,0,0,0.08)";
                    }}
                >
                    <div>
                        <h3
                            style={{
                                margin: "0 0 12px 0",
                                fontSize: "1.25rem",
                                color: "#1a1a1a",
                                fontWeight: "700",
                            }}
                        >
                            {plat.name}
                        </h3>
                        <p
                            style={{
                                fontSize: "14px",
                                color: "#666",
                                lineHeight: "1.6",
                                marginBottom: "20px",
                            }}
                        >
                            {plat.description}
                        </p>
                    </div>

                    <div
                        style={{
                            display: "flex",
                            justifyContent: "space-between",
                            alignItems: "center",
                        }}
                    >
                        <span
                            style={{
                                fontSize: "1.2rem",
                                fontWeight: "800",
                                color: "#27ae60",
                            }}
                        >
                            {plat.price}{" "}
                            <small style={{ fontSize: "12px", color: "#888" }}>
                                DH
                            </small>
                        </span>
                        <button
                            style={{
                                backgroundColor: "#000",
                                color: "#fff",
                                border: "none",
                                padding: "10px 18px",
                                borderRadius: "12px",
                                fontSize: "14px",
                                fontWeight: "600",
                                cursor: "pointer",
                                transition: "background 0.2s",
                            }}
                        >
                            Add to Cart
                        </button>
                    </div>
                </div>
            ))}
        </div>
    );

    // const [search, setSearch] = useState("");

    // const filtered = Plats.filter((plat) =>
    //   plat.name.toLowerCase().includes(search.toLowerCase())
    // );

    // const [selectedCount, setSelectedCount] = useState(0);

    // function handleSelect(isSelected) {
    //   if (isSelected) {
    //     setSelectedCount((prev) => prev + 1);
    //   } else {
    //     setSelectedCount((prev) => prev - 1);
    //   }
    // }

    // return (
    //   <div>
    //     <h2>My Cards</h2>

    //     <h3>Le nombre des plats sélectionnés est : {selectedCount}</h3>

    //     <input
    //       type="text"
    //       placeholder="Search here"
    //       value={search}
    //       onChange={(e) => setSearch(e.target.value)}
    //     />

    //     <p>
    //       ============================================================
    //     </p>

    //     {filtered.length === 0 && <p>Aucun plat trouvé</p>}

    //     {filtered.map((plat) => (
    //       <PlateCard key={plat.id} {...plat} onSelect={handleSelect} />
    //     ))}

    //   </div>
    // );
}

export default Plates;
