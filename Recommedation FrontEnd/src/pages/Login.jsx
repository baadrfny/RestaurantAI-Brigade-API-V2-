import React, { useState } from 'react';
import api, { getApiErrorMessage } from '../api/axios';
import { useNavigate } from 'react-router-dom';
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useAuth } from '../context/AuthContext';


const loginSchema = z.object({
    email: z.string().email("Email incorrect!"),
    password: z.string().min(8, "Minimum 8 caractères")
})


function Login (){
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();
    const { login } = useAuth();

    const { 
        register, 
        handleSubmit, 
        formState: { errors, isValid } 
    } = useForm({
        resolver: zodResolver(loginSchema),
        mode: 'onChange'
    });

    

    const handleLogin = async (data) => {
        setLoading(true);
        try {

            await login(data.email, data.password);
            alert('Login successful');
            navigate('/plates');
        } catch (err) {
            alert(getApiErrorMessage(err, "Login failed. Please check your credentials."));
        } finally {
            setLoading(false);
        }
    };

    return (
        <form 
            onSubmit={handleSubmit(handleLogin)} 
            style={{ padding: '20px', maxWidth: '400px', display: 'flex', flexDirection: 'column', gap: '10px' }}
        >
            <h2>Login</h2>
            
            <div>
                <input 
                    type="email" 
                    placeholder="Email" 
                    id="email"
                    {...register("email")} 
                />
                {errors.email && <p style={{ color: 'red', fontSize: '12px' }}>{errors.email.message}</p>}
            </div>

            <div>
                <input 
                    type="password" 
                    placeholder="Password" 
                    id="password"
                    {...register("password")} 
                />
                {errors.password && <p style={{ color: 'red', fontSize: '12px' }}>{errors.password.message}</p>}
            </div>

            <button type="submit" disabled={loading || !isValid}>
                {loading ? "Loading..." : "Login"}
            </button>
        </form>
    );

}

    export default Login;
