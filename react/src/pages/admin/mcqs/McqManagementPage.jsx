import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemListing.module.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const McqManagementPage = () => {
    const [mcqs, setMcqs] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
    const navigate = useNavigate();

    useEffect(() => {
        fetchMcqs(currentPage);
    }, [currentPage]);

    const fetchMcqs = (page) => {
        privateApi.get(`/mcqs?page=${page}`)
            .then(response => {
                const { data, current_page, last_page } = response.data.data;
                setMcqs(data || []);
                setCurrentPage(current_page);
                setLastPage(last_page);
            })
            .catch(error => {
                console.error('Error fetching MCQs:', error);
                setMcqs([]);
            });
    };

    const handleView = (id) => {
        navigate(`/admin/mcqs/${id}`);
    };

    const handleEdit = (id) => {
        navigate(`/admin/mcqs/${id}/edit`);
    };

    const handleDelete = (id) => {
        if (window.confirm('Are you sure you want to delete this MCQ?')) {
            privateApi.delete(`/mcqs/${id}`)
                .then(() => {
                    setMcqs(mcqs.filter(mcq => mcq.id !== id));
                })
                .catch(error => {
                    console.error('Error deleting MCQ:', error);
                });
        }
    };

    const handlePageChange = (page) => {
        if (page >= 1 && page <= lastPage) {
            setCurrentPage(page);
        }
    };

    return (
        <div className={`ItemsViewContainer ${styles.ItemsViewContainer}`}>
            <div className="d-flex justify-content-between align-items-center">
                <h1 className="text-primary">The List of MCQs</h1>
                <button onClick={() => navigate('/admin/mcqs/create')} className="btn btn-success">Create New MCQ</button>
            </div>
            <table className="table table-striped table-hover">
                <thead className="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Question</th>
                        <th scope="col">Correct Answer</th>
                        <th scope="col">Points</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {mcqs.map((mcq, index) => (
                        <tr key={mcq.id}>
                            <td scope="row">{index + 1 + (currentPage - 1) * 15}</td>
                            <td>{mcq.question}</td>
                            <td>{mcq.correct_answer}</td>
                            <td>{mcq.points}</td>
                            <td>
                                <button onClick={() => handleView(mcq.id)} className="btn btn-info btn-sm me-2">View</button>
                                <button onClick={() => handleEdit(mcq.id)} className="btn btn-warning btn-sm me-2">Edit</button>
                                <button onClick={() => handleDelete(mcq.id)} className="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <div className="d-flex justify-content-between align-items-center mt-3">
                <button
                    className="btn btn-secondary"
                    onClick={() => handlePageChange(currentPage - 1)}
                    disabled={currentPage === 1}
                >
                    Previous
                </button>
                <span>Page {currentPage} of {lastPage}</span>
                <button
                    className="btn btn-secondary"
                    onClick={() => handlePageChange(currentPage + 1)}
                    disabled={currentPage === lastPage}
                >
                    Next
                </button>
            </div>
        </div>
    );
};

export default McqManagementPage;