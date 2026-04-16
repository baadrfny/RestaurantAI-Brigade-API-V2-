import { NavLink, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

function Navbar() {
  const { user, isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  async function handleLogout() {
    await logout();
    navigate("/login");
  }

  return (
    <nav style={{ marginBottom: "20px" }}>
      <NavLink to="/" style={{ marginRight: "15px" }}>
        Home
      </NavLink>

      <NavLink to="/plates" style={{ marginRight: "15px" }}>
        Plates
      </NavLink>

      <NavLink to="/profile" style={{ marginRight: "15px" }}>
        Profile 
      </NavLink>
      
      <NavLink to="/users" style={{ marginRight: "15px" }}>
        users
      </NavLink>

      {!isAuthenticated && (
        <>
          <NavLink to="/login" style={{ marginRight: "15px" }}>
            Login
          </NavLink>

          <NavLink to="/register" style={{ marginRight: "15px" }}>
            Register
          </NavLink>
        </>
      )}

      {isAuthenticated && (
        <>
          <span style={{ marginRight: "15px" }}>
            Connected: {user?.name ?? "User"}
          </span>
          <button type="button" onClick={handleLogout}>
            Logout
          </button>
        </>
      )}

    </nav>
  );
}

export default Navbar;
