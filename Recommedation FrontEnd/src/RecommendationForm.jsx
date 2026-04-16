import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";

// (Schema)
const schema = z.object({
  title: z.string().min(3, "Title must be 3+ chars"),
  description: z.string().min(1, "Description is required"),
});

const RecommendationForm = () => {
  
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
  });

  const onSubmit = (data) => {
    console.log("Form Data:", data);
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input {...register("title")} placeholder="Recommendation Title" />
      {errors.title && <p>{errors.title.message}</p>}

      <textarea {...register("description")} placeholder="Details..." />
      {errors.description && <p>{errors.description.message}</p>}

      <button type="submit">Send</button>
    </form>
  );
};

export default RecommendationForm;