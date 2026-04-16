import React, { useState } from 'react';
import api, { getApiErrorMessage } from '../api/axios';
import { useNavigate } from 'react-router-dom';
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";

const registerSchema = z.object({
  name: z.string().min(3, "Name must be more than 3 characters"),
  email: z.string().email("Email is not correct"),
  password: z.string().min(8, "Password must be at least 8 characters"),
  confirmPassword: z.string().min(1, "Please confirm your password"),
}).refine(data => data.password === data.confirmPassword, {
  message: "Passwords don't match",
  path: ["confirmPassword"],
});

export function Register() {
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();


  const { 
    register, 
    handleSubmit, 
    formState: { errors, isValid } 
  } = useForm({
    resolver: zodResolver(registerSchema),
    mode: 'onChange',
  });


  const onSubmit = async (data) => {
    setLoading(true);
    try {
      await api.post('/register', { 
        ...data, 
        password_confirmation: data.password 
      });
      
      alert("Account created successfully!");
      navigate('/login'); 
    } catch (err) {
      alert(getApiErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ display: 'flex', justifyContent: 'center', marginTop: '50px' }}>
      <form 
        onSubmit={handleSubmit(onSubmit)} 
        style={formStyle}
      >
        <h2 style={{ textAlign: 'center', marginBottom: '20px' }}>Create Account</h2>
        
        <div style={{ marginBottom: '15px' }}>
          <label>Full Name</label>
          <input 
            type="text" 
            placeholder="Enter your name" 
            style={inputStyle}
            {...register("name")}
          />
          {errors.name && <p style={errorStyle}>{errors.name.message}</p>}
        </div>

        <div style={{ marginBottom: '15px' }}>
          <label>Email Address</label>
          <input 
            type="email" 
            placeholder="Enter your email" 
            style={inputStyle}
            {...register("email")}
          />
          {errors.email && <p style={errorStyle}>{errors.email.message}</p>}
        </div>

        <div style={{ marginBottom: '20px' }}>
          <label>Password</label>
          <input 
            type="password" 
            placeholder="Create a password" 
            style={inputStyle}
            {...register("password")}
          />
          {errors.password && <p style={errorStyle}>{errors.password.message}</p>}
        </div>

        <div style={{ marginBottom: '20px' }}>
          <label>Confirm Password</label>
          <input 
            type="password" 
            placeholder="Confirm your password" 
            style={inputStyle}
            {...register("confirmPassword")}
          />
          {errors.confirmPassword && <p style={errorStyle}>{errors.confirmPassword.message}</p>}
        </div>

        <button 
          type="submit" 
          disabled={loading || !isValid}
          style={buttonStyle(loading)}
        >
          {loading ? "Registering..." : "Sign Up"}
        </button>
      </form>
    </div>
  );
}

const inputStyle = { width: '100%', padding: '10px', marginTop: '5px', borderRadius: '6px', border: '1px solid #ccc', boxSizing: 'border-box' };
const errorStyle = { color: 'red', fontSize: '12px', marginTop: '5px' };
const formStyle = { padding: '30px', border: '1px solid #ddd', borderRadius: '12px', width: '100%', maxWidth: '400px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' };
const buttonStyle = (loading) => ({ width: '100%', padding: '12px', backgroundColor: '#007bff', color: 'white', border: 'none', borderRadius: '6px', cursor: loading ? 'not-allowed' : 'pointer' });

export default Register;