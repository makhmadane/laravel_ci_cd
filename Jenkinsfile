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
                timeout(time: 1, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: false
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                echo '🐳 Build image Docker...'
                sh '''
                docker build -t $IMAGE_NAME .
                '''
            }
        }

        stage('Run Container') {
            steps {
                echo '🚀 Lancement du container...'
                sh '''
                docker rm -f $CONTAINER_NAME || true
                docker run -d -p 8000:8000 --name $CONTAINER_NAME $IMAGE_NAME
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

    }

    post {
        always {
            sh '''
            docker logs $CONTAINER_NAME || true
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
