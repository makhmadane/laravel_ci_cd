pipeline {
    agent any

    environment {
        IMAGE_NAME = "khadimlo1996/laravel-cd-ci-image"
        LOCAL_IMAGE = "laravel-cd-ci-app"
        IMAGE_TAG = "${BUILD_NUMBER}"
        NEXUS_HOST = "host.docker.internal"
        NEXUS_PORT = "5000"
        REPO_NAME = "docker-hosted"
        FULL_IMAGE = "${NEXUS_HOST}:${NEXUS_PORT}/${REPO_NAME}/${IMAGE_NAME}:${IMAGE_TAG}"
    }

    stages {
        stage('Checkout') {
            steps {
                echo '📥 Cloning repository...'
                git branch: 'main2',
                    credentialsId: 'github-credentials',
                    url: 'https://github.com/makhmadane/laravel_ci_cd.git'
            }
        }

        stage('Build Docker Compose') {
            steps {
                echo '🐳 Build des images via docker-compose...'
                sh 'docker-compose build'
            }
        }

        stage('Start Services') {
            steps {
                echo '🚀 Lancement des services...'
                sh '''
                docker-compose down || true
                docker-compose up -d
                '''
            }
        }

        stage('Wait for MySQL') {
            steps {
                echo '⏳ Attente que MySQL soit prêt...'
                sh '''
                for i in $(seq 1 30); do
                    docker-compose exec -T mysql mysqladmin ping -h localhost -u root -proot --silent && break
                    echo "Waiting for MySQL... ($i/30)"
                    sleep 2
                done
                '''
            }
        }

        stage('Laravel Setup') {
            steps {
                echo '⚙️ Configuration Laravel...'
                sh '''
                docker-compose exec -T app cp .env.example .env || true
                docker-compose exec -T app php artisan key:generate
                docker-compose exec -T app php artisan config:clear
                docker-compose exec -T app php artisan migrate --force || true
                '''
            }
        }

        stage('Run Tests') {
            steps {
                echo '🧪 Exécution des tests...'
                sh '''
                docker-compose exec -T app php artisan test
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

        stage('Tag & Push to Docker Hub') {
            steps {
                sh """
                docker tag $LOCAL_IMAGE:latest $IMAGE_NAME:$IMAGE_TAG
                docker push $IMAGE_NAME:$IMAGE_TAG
                """
            }
        }

        stage('Login to Nexus') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'nexus-docker-creds',
                    usernameVariable: 'NEXUS_USER',
                    passwordVariable: 'NEXUS_PASS'
                )]) {
                    sh '''
                    echo "$NEXUS_PASS" | docker login $NEXUS_HOST:$NEXUS_PORT \
                      -u "$NEXUS_USER" --password-stdin
                    '''
                }
            }
        }

        stage('Tag & Push to Nexus') {
            steps {
                sh '''
                docker tag $LOCAL_IMAGE:latest $FULL_IMAGE
                docker push $FULL_IMAGE
                '''
            }
        }
    }

    post {
        always {
            sh '''
            docker-compose logs || true
            docker-compose down || true
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
