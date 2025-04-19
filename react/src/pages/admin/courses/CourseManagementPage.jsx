import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from './CourseManagement.module.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const CourseManagementPage = () => {
    const [courses, setCourses] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        privateApi.get('/courses')
            .then(response => {
                const { data } = response.data.data;
                if (Array.isArray(data)) {
                    setCourses(data);
                } else {
                    setCourses([]);
                }
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                setCourses([]);
            });
    }, []);

    const handleView = (id) => {
        navigate(`/admin/courses/${id}`);
    };

    const handleEdit = (id) => {
        navigate(`/admin/courses/${id}/edit`);
    };

    const handleDelete = (id) => {
        if (window.confirm('Are you sure you want to delete this course?')) {
            privateApi.delete(`/courses/${id}`)
                .then(() => {
                    setCourses(courses.filter(course => course.id !== id));
                })
                .catch(error => {
                    console.error('Error deleting course:', error);
                });
        }
    };

    return (
        <div className={`container ${styles.courseManagement}`}>
            <div className="d-flex justify-content-between align-items-center">
                <h1 className="text-primary">The List of Courses</h1>
                <button onClick={() => navigate('/admin/courses/create')} className="btn btn-success">Create New Course</button>
            </div>
            <table className="table table-striped table-hover">
                <thead className="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {courses.map((course, index) => (
                        <tr key={course.id}>
                            <td scope="row">{index + 1}</td>
                            <td>{course.title}</td>
                            <td className="text-end">
                                <button onClick={() => handleView(course.id)} className="btn btn-info btn-sm me-2">View</button>
                                <button onClick={() => handleEdit(course.id)} className="btn btn-warning btn-sm me-2">Edit</button>
                                <button onClick={() => handleDelete(course.id)} className="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default CourseManagementPage;