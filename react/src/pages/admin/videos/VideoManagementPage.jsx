import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemListing.module.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const VideoManagementPage = () => {
    const [videos, setVideos] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
    const navigate = useNavigate();

    useEffect(() => {
        fetchVideos(currentPage);
    }, [currentPage]);

    const fetchVideos = (page) => {
        privateApi.get(`/videos?page=${page}`)
            .then(response => {
                const { data, current_page, last_page } = response.data.data;
                setVideos(data || []);
                setCurrentPage(current_page);
                setLastPage(last_page);
            })
            .catch(error => {
                console.error('Error fetching videos:', error);
                setVideos([]);
            });
    };

    const handleView = (id) => {
        navigate(`/admin/videos/${id}`);
    };

    const handleEdit = (id) => {
        navigate(`/admin/videos/${id}/edit`);
    };

    const handleDelete = (id) => {
        if (window.confirm('Are you sure you want to delete this video?')) {
            privateApi.delete(`/videos/${id}`)
                .then(() => {
                    setVideos(videos.filter(video => video.id !== id));
                })
                .catch(error => {
                    console.error('Error deleting video:', error);
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
                <h1 className="text-primary">The List of Videos</h1>
                <button onClick={() => navigate('/admin/videos/create')} className="btn btn-success">Create New Video</button>
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
                    {videos.map((video, index) => (
                        <tr key={video.id}>
                            <td scope="row">{index + 1 + (currentPage - 1) * 15}</td>
                            <td>{video.title}</td>
                            <td>
                                <button onClick={() => handleView(video.id)} className="btn btn-info btn-sm me-2">View</button>
                                <button onClick={() => handleEdit(video.id)} className="btn btn-warning btn-sm me-2">Edit</button>
                                <button onClick={() => handleDelete(video.id)} className="btn btn-danger btn-sm">Delete</button>
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

export default VideoManagementPage;