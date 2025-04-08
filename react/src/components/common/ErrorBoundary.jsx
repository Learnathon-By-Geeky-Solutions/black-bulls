import React from 'react';
import { Card, Button } from 'react-bootstrap';
import PropTypes from 'prop-types';

class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false, error: null, errorInfo: null };
    }

    static getDerivedStateFromError(error) {
        return { hasError: true };
    }

    componentDidCatch(error, errorInfo) {
        this.setState({
            error: error,
            errorInfo: errorInfo
        });
        // You can also log the error to an error reporting service here
        console.error('Error caught by boundary:', error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            return (
                <Card className="m-3 text-center">
                    <Card.Body>
                        <Card.Title className="text-danger">Something went wrong</Card.Title>
                        <Card.Text>
                            We apologize for the inconvenience. Please try refreshing the page.
                        </Card.Text>
                        <Button 
                            variant="primary" 
                            onClick={() => window.location.reload()}
                        >
                            Refresh Page
                        </Button>
                    </Card.Body>
                </Card>
            );
        }

        return this.props.children;
    }
}

ErrorBoundary.propTypes = {
    children: PropTypes.node.isRequired
};

export default ErrorBoundary; 