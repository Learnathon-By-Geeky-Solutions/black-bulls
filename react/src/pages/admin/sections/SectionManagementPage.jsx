import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemListing.module.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const SectionManagementPage = () => {
    const [sections, setSections] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        privateApi.get('/sections')
            .then(response => {
                const { data } = response.data.data;
                if (Array.isArray(data)) {
                    setSections(data);
                } else {
                    setSections([]);
                }
            })
            .catch(error => {
                console.error('Error fetching sections:', error);
                setSections([]);
            });
    }, []);

    const handleView = (id) => {
        navigate(`/admin/sections/${id}`);
    };

    const handleEdit = (id) => {
        navigate(`/admin/sections/${id}/edit`);
    };

    const handleDelete = (id) => {
        if (window.confirm('Are you sure you want to delete this section?')) {
            privateApi.delete(`/sections/${id}`)
                .then(() => {
                    setSections(sections.filter(section => section.id !== id));
                })
                .catch(error => {
                    console.error('Error deleting section:', error);
                });
        }
    };

    return (
        <div className={`ItemsViewContainer ${styles.ItemsViewContainer}`}>
            <div className="d-flex justify-content-between align-items-center">
                <h1 className="text-primary">The List of Sections</h1>
                <button onClick={() => navigate('/admin/sections/create')} className="btn btn-success">Create New Section</button>
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
                    {sections.map((section, index) => (
                        <tr key={section.id}>
                            <td scope="row">{index + 1}</td>
                            <td>{section.title}</td>
                            <td>
                                <button onClick={() => handleView(section.id)} className="btn btn-info btn-sm me-2">View</button>
                                <button onClick={() => handleEdit(section.id)} className="btn btn-warning btn-sm me-2">Edit</button>
                                <button onClick={() => handleDelete(section.id)} className="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default SectionManagementPage;