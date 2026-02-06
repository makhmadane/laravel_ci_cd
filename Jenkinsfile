pipeline {
    agent any

    environment {
        APP_NAME = "laravel-cd-ci"
        IMAGE_NAME = "khadimlo1996/laravel-cd-ci-image"
        CONTAINER_NAME = "laravel-cd-ci-container"
        IMAGE_TAG = "${BUILD_NUMBER}"
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
                echo '🐳 Build image Docker...'
                sh """
                docker build -t $IMAGE_NAME:$IMAGE_TAG .
                """
            }
        }

        stage('Run Container') {
            steps {
                echo '🚀 Lancement du container...'
                sh '''
                docker rm -f $CONTAINER_NAME || true
                docker run -d -p 8000:8000 --name $CONTAINER_NAME $IMAGE_NAME:$IMAGE_TAG
                '''
            }
        }

        stage('Laravel Setup') {
            steps {
                echo '⚙️ Configuration Laravel...'
                sh '''
                docker exec $CONTAINER_NAME cp .env.example .env || true
                docker exec $CONTAINER_NAME php artisan key:generate
                docker exec $CONTAINER_NAME php artisan config:clear
                '''
            }
        }

        stage('Run Tests') {
            steps {
                echo '🧪 Exécution des tests...'
                sh '''
                docker exec $CONTAINER_NAME php artisan test
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                echo '🔍 Analyse SonarQube...'
                script {
                    def scannerHome = tool 'SonarScanner'
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }

        stage('Quality Gate') {
            steps {
                echo '🚦 Vérification Quality Gate...'
                timeout(time: 5, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Login Docker Hub') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub-credentials',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    sh """
                    echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                    """
                }
            }
        }

        stage('Push Image') {
            steps {
                sh """
                docker push $IMAGE_NAME:$IMAGE_TAG
                """
            }
        }
    }

    post {
        always {
            sh '''
            docker logs $CONTAINER_NAME || true
            docker rm -f $CONTAINER_NAME || true
            '''
        }

        success {
            echo "✅ Pipeline aligné et Quality Gate respecté"
        }

        failure {
            echo "❌ Pipeline bloqué (tests ou Quality Gate KO)"
        }
    }
}
