pipeline {
    agent any

    environment {
        APP_NAME = "laravel-cd-ci"
        IMAGE_NAME = "laravel-cd-ci-image"
        CONTAINER_NAME = "laravel-cd-ci-container"
    }

    stages {

        stage('Checkout') {
            steps {
                echo '📥 Cloning repository...'
                git branch: 'main',
                    credentialsId: 'github-credentials',
                    url: 'https://github.com/makhmadane/laravel_ci_cd.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh '''
                docker build -t $IMAGE_NAME .
                '''
            }
        }

        stage('Run Container') {
            steps {
                sh '''
                docker rm -f $CONTAINER_NAME || true
                docker run -d -p 8000:8000 --name $CONTAINER_NAME $IMAGE_NAME
                '''
            }
        }

        stage('Laravel Setup') {
            steps {
                sh '''
                docker exec $CONTAINER_NAME cp .env.example .env || true
                docker exec $CONTAINER_NAME php artisan key:generate
                docker exec $CONTAINER_NAME php artisan config:clear
                '''
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                docker exec $CONTAINER_NAME php artisan test || true
                '''
            }
        }
    }

    post {
        always {
            sh '''
            docker logs $CONTAINER_NAME || true
            '''
        }

        success {
            echo "✅ Pipeline Laravel exécuté avec succès"
        }

        failure {
            echo "❌ Pipeline Laravel échoué"
        }
    }
}
